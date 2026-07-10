<?php
namespace Concrete\Package\ContainerMaker\Controller\SinglePage\Dashboard\Pages;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Page\Theme\Theme as PageTheme;
use Concrete\Package\ContainerMaker\Service\ContainerGenerator;
use Concrete\Package\ContainerMaker\Service\ContainerImporter;
use Concrete\Package\ContainerMaker\Service\ContainerManager;

class ContainerMaker extends DashboardPageController
{
    public function view()
    {
        $themes = $this->getInstalledThemes();
        $themeDebug = null;
        $activeTheme = $this->resolveActiveTheme($themeDebug);

        // The designer always works against the active site theme so containers
        // are never accidentally created against, or imported from, another theme.
        $selectedThemeID = 0;
        if (is_object($activeTheme) && $activeTheme->getThemeID()) {
            $selectedThemeID = (int) $activeTheme->getThemeID();
        }
        if ($selectedThemeID <= 0) {
            $selectedThemeID = (int) $this->request->query->get('themeID');
        }
        if ($selectedThemeID <= 0 && !empty($themes)) {
            $selectedThemeID = (int) $themes[0]['id'];
        }

        $theme = $this->resolveThemeByID($selectedThemeID);
        if (!is_object($theme) || !$theme->getThemeID()) {
            $theme = $activeTheme;
            if (is_object($theme) && $theme->getThemeID()) {
                $selectedThemeID = (int) $theme->getThemeID();
            }
        }

        $registeredContainers = [];
        $themeContainerFiles = [];
        $themeHasBootstrap = false;
        if ($selectedThemeID > 0) {
            $themeForFiles = is_object($theme) && $theme->getThemeID() ? $theme : $this->resolveThemeByID($selectedThemeID);
            if (is_object($themeForFiles) && $themeForFiles->getThemeID()) {
                $manager = $this->app->make(ContainerManager::class);
                $registeredContainers = $manager->getRegisteredContainersForTheme($themeForFiles);
                $themeContainerFiles = $manager->getThemeContainerFiles($themeForFiles);
                $themeHasBootstrap = $manager->themeHasBootstrap($themeForFiles);
            }
        }

        $editingContainer = null;
        $importWarnings = [];
        $editHandle = (string) $this->request->query->get('edit');
        if ($editHandle !== '' && is_object($theme) && $theme->getThemeID()) {
            [$editingContainer, $importWarnings] = $this->loadEditingContainer($theme, $registeredContainers, $editHandle);
            if ($editingContainer === null) {
                $this->error->add(t('That container could not be loaded for editing.'));
            } else {
                $editingContainer['theme_id'] = (int) $theme->getThemeID();
                foreach ($importWarnings as $warning) {
                    $this->flash('info', $warning);
                }
            }
        }

        $this->set('themes', $themes);
        $this->set('activeTheme', $activeTheme);
        $this->set('theme', $theme);
        $this->set('selectedThemeID', $selectedThemeID);
        $this->set('canSave', !empty($themes));
        $this->set('themeDebug', $themeDebug ?? null);
        $this->set('registeredContainers', $registeredContainers);
        $this->set('themeContainerFiles', $themeContainerFiles);
        $this->set('themeHasBootstrap', $themeHasBootstrap);
        $this->set('editingContainer', $editingContainer);
        $this->set('importWarnings', $importWarnings);
    }

    public function import()
    {
        if (!$this->token->validate('container_maker_import')) {
            $this->error->add($this->token->getErrorMessage());
            return $this->view();
        }

        $theme = $this->resolveActiveTheme();
        if (!is_object($theme) || !$theme->getThemeID()) {
            $theme = $this->resolveThemeByID((int) $this->request->request->get('themeID'));
        }
        if (!is_object($theme) || !$theme->getThemeID()) {
            $this->error->add(t('Could not determine the active theme.'));
            return $this->view();
        }

        $importer = $this->app->make(ContainerImporter::class);
        $manager = $this->app->make(ContainerManager::class);
        $source = (string) $this->request->request->get('importSource');
        $handle = (string) $this->request->request->get('importHandle');
        $paste = trim((string) $this->request->request->get('importPaste'));
        $upload = $this->request->files->get('importFile');

        if ($source === 'paste') {
            if ($paste === '') {
                $this->error->add(t('Paste container PHP code to import.'));
                return $this->view();
            }
            if ($handle === '') {
                $handle = 'imported_container';
            }
            $result = $importer->importFromContents($paste, $handle);
        } elseif ($source === 'upload') {
            if (!is_object($upload) || !$upload->isValid()) {
                $this->error->add(t('Choose a container PHP file to upload.'));
                return $this->view();
            }
            $extension = strtolower(pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION));
            if ($extension !== 'php') {
                $this->error->add(t('Upload a .php container file.'));
                return $this->view();
            }
            if ($handle === '') {
                $handle = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);
            }
            $contents = @file_get_contents($upload->getPathname());
            if ($contents === false) {
                $this->error->add(t('Could not read uploaded file.'));
                return $this->view();
            }
            $result = $importer->importFromContents($contents, $handle);
        } else {
            if ($handle === '') {
                $this->error->add(t('Choose a container to import.'));
                return $this->view();
            }
            $path = $manager->resolveThemeContainerPath($theme, $handle);
            if (!$path) {
                $this->error->add(t('Container file not found in the selected theme.'));
                return $this->view();
            }
            $result = $importer->importFromPath($path, $handle);
        }

        if (!empty($result['error'])) {
            $this->error->add($result['error']);
            return $this->view();
        }

        $state = $result['state'] ?? null;
        if (!is_array($state)) {
            $this->error->add(t('Import failed.'));
            return $this->view();
        }

        $state['theme_id'] = (int) $theme->getThemeID();
        $state['imported'] = true;
        if (($state['import_source'] ?? '') === 'parsed' && empty($state['imported_parsed'])) {
            $state['imported_parsed'] = true;
        }

        foreach ($result['warnings'] ?? [] as $warning) {
            $this->flash('info', $warning);
        }

        $this->flash('success', t('Imported container %s into the designer.', $state['name'] ?? $handle));

        if ($source === 'paste' || $source === 'upload') {
            $state['import_allow_handle'] = true;
            $this->storeImportedState((string) ($state['handle'] ?? $handle), $state, $result['warnings'] ?? []);
        } elseif (($state['import_source'] ?? '') === 'parsed') {
            $state['imported_parsed'] = true;
        }

        $redirectHandle = rawurlencode((string) ($state['handle'] ?? $handle));
        return $this->buildRedirect('/dashboard/pages/container_maker?themeID=' . (int) $theme->getThemeID() . '&edit=' . $redirectHandle . '&imported=1');
    }

    public function save()
    {
        if (!$this->token->validate('container_maker_save')) {
            $this->error->add($this->token->getErrorMessage());
            return $this->view();
        }

        $theme = $this->resolveActiveTheme();
        if (!is_object($theme) || !$theme->getThemeID()) {
            $theme = $this->resolveThemeByID((int) $this->request->request->get('themeID'));
        }
        if (!is_object($theme) || !$theme->getThemeID()) {
            $this->error->add(t('Could not determine the active theme.'));
            return $this->view();
        }

        $data = $this->request->request->all();
        $data['theme_id'] = (int) $theme->getThemeID();

        $generator = $this->app->make(ContainerGenerator::class);
        $result = $generator->generate($theme, $data);

        foreach ($result['created'] as $item) {
            $this->flash('success', t('Created container file: %s', $item));
        }
        foreach ($result['registered'] as $item) {
            $this->flash('success', t('Registered %s', $item));
        }
        foreach ($result['skipped'] as $item) {
            $this->flash('info', t('Skipped existing file %s. Check overwrite if you want to replace it.', $item));
        }
        foreach ($result['errors'] as $item) {
            $this->error->add($item);
        }

        $editHandle = trim((string) ($data['handle'] ?? ''));
        if ($editHandle !== '') {
            return $this->buildRedirect('/dashboard/pages/container_maker?themeID=' . (int) $theme->getThemeID() . '&edit=' . rawurlencode($editHandle));
        }

        return $this->buildRedirect('/dashboard/pages/container_maker?themeID=' . (int) $theme->getThemeID());
    }

    /** @deprecated Use save() — kept so old bookmarks still work. */
    public function build_flex()
    {
        return $this->save();
    }

    public function uninstall()
    {
        if (!$this->token->validate('container_maker_uninstall')) {
            $this->error->add($this->token->getErrorMessage());
            return $this->view();
        }

        $handle = (string) $this->request->request->get('containerHandle');
        $themeID = (int) $this->request->request->get('themeID');
        $confirm = (bool) $this->request->request->get('confirm');

        if (!$confirm) {
            $this->error->add(t('Please confirm before uninstalling a container.'));
            return $this->buildRedirect('/dashboard/pages/container_maker' . ($themeID ? '?themeID=' . $themeID : ''));
        }

        $manager = $this->app->make(ContainerManager::class);
        $result = $manager->uninstallByHandle($handle);
        if ($result === true) {
            $this->flash('success', t('Uninstalled container registration: %s', $handle));
        } else {
            $this->error->add($result);
        }

        return $this->buildRedirect('/dashboard/pages/container_maker' . ($themeID ? '?themeID=' . $themeID : ''));
    }

    protected function loadEditingContainer(PageTheme $theme, array $registeredContainers, $handle)
    {
        $handle = preg_replace('/[^A-Za-z0-9_\-]/', '', (string) $handle);
        if ($handle === '') {
            return [null, []];
        }

        if ($this->request->query->get('imported')) {
            $sessionState = $this->consumeImportedState($handle);
            if (is_array($sessionState)) {
                $sessionState['handle'] = $handle;
                $sessionState['theme_id'] = (int) $theme->getThemeID();

                return [$sessionState, $sessionState['import_warnings'] ?? []];
            }
        }

        $path = null;
        $displayName = $this->humanizeHandle($handle);
        foreach ($registeredContainers as $container) {
            if (($container['handle'] ?? '') !== $handle) {
                continue;
            }
            $displayName = $container['name'] ?? $displayName;
            if (!empty($container['template_path']) && is_file($container['template_path'])) {
                $path = $container['template_path'];
            }
            break;
        }

        if (!$path) {
            $manager = $this->app->make(ContainerManager::class);
            $path = $manager->resolveThemeContainerPath($theme, $handle);
        }

        if (!$path || !is_file($path)) {
            return [null, []];
        }

        $importer = $this->app->make(ContainerImporter::class);
        $result = $importer->importFromPath($path, $handle);
        if (!empty($result['error']) || empty($result['state']) || !is_array($result['state'])) {
            return [null, []];
        }

        $state = $result['state'];
        $state['handle'] = $handle;
        $state['name'] = $state['name'] ?? $displayName;
        if (($state['import_source'] ?? '') === 'parsed') {
            $state['imported_parsed'] = true;
        }

        return [$state, $result['warnings'] ?? []];
    }

    protected function storeImportedState($handle, array $state, array $warnings = [])
    {
        $state['import_warnings'] = $warnings;
        $this->app->make('session')->set('container_maker.import.' . $handle, $state);
    }

    protected function consumeImportedState($handle)
    {
        $handle = preg_replace('/[^A-Za-z0-9_\-]/', '', (string) $handle);
        if ($handle === '') {
            return null;
        }

        $session = $this->app->make('session');
        $key = 'container_maker.import.' . $handle;
        if (!$session->has($key)) {
            return null;
        }

        $state = $session->get($key);
        $session->remove($key);

        return is_array($state) ? $state : null;
    }

    protected function humanizeHandle($handle)
    {
        $handle = str_replace(['_', '-'], ' ', (string) $handle);

        return ucwords(trim($handle));
    }

    protected function getInstalledThemes()
    {
        $rows = [];
        foreach (PageTheme::getList() as $theme) {
            if (!is_object($theme) || !$theme->getThemeID()) {
                continue;
            }
            $rows[] = [
                'id' => (int) $theme->getThemeID(),
                'name' => $theme->getThemeName(),
                'handle' => $theme->getThemeHandle(),
            ];
        }
        usort($rows, static function ($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });

        return $rows;
    }

    protected function resolveThemeByID($themeID)
    {
        $themeID = (int) $themeID;
        if ($themeID <= 0) {
            return null;
        }
        $theme = PageTheme::getByID($themeID);

        return (is_object($theme) && $theme->getThemeID()) ? $theme : null;
    }

    protected function resolveActiveTheme(&$debug = null)
    {
        try {
            $theme = PageTheme::getSiteTheme();
            if (is_object($theme) && $theme->getThemeID()) {
                return $theme;
            }
        } catch (\Throwable $e) {
            $debug = 'getSiteTheme() threw: ' . $e->getMessage();
        }

        try {
            $db = \Database::connection();
            $themeID = (int) $db->fetchOne('SELECT pThemeID FROM Sites ORDER BY siteID ASC LIMIT 1');
        } catch (\Throwable $e) {
            $themeID = 0;
            $debug = ($debug ? $debug . ' | ' : '') . 'Direct Sites query failed: ' . $e->getMessage();
        }

        if ($themeID > 0) {
            $fresh = PageTheme::getByID($themeID);
            if (is_object($fresh) && $fresh->getThemeID()) {
                return $fresh;
            }
            $debug = ($debug ? $debug . ' | ' : '') . 'Sites.pThemeID=' . $themeID . ' but theme record missing.';
        } else {
            $debug = ($debug ? $debug . ' | ' : '') . 'Sites.pThemeID is empty.';
        }

        return null;
    }
}

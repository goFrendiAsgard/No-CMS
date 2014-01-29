        $this->add_navigation($this->cms_complete_navigation_name('{{ back_navigation_name }}'), 'Manage {{ table_caption }}',
            $module_path.'/{{ back_controller_name }}', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('{{ navigation_parent_name }}')
        );
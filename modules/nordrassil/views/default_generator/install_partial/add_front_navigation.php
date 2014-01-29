        $this->add_navigation($this->cms_complete_navigation_name('{{ front_navigation_name }}'), 'Browse {{ table_caption }}',
            $module_path.'/{{ front_controller_name }}', $this->PRIV_EVERYONE, $this->cms_complete_navigation_name('{{ navigation_parent_name }}')
        );
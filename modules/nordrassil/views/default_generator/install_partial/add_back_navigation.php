        $this->add_navigation({{ back_navigation_name }}, "Manage {{ table_caption }}", 
            $module_path."/{{ back_controller_name }}", $this->PRIV_AUTHORIZED, {{ navigation_parent_name }}
        );
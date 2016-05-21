        // {{ back_navigation_name }}
        $this->cms_add_navigation(
                $this->n('{{ back_navigation_name }}'),  //  Navigation name
                'Manage {{ table_caption }}',  // Title
                $module_path.'/{{ back_controller_name }}',  // URL Path 
                PRIV_AUTHORIZED,   // Authorization
                $this->n('{{ navigation_parent_name }}'),  // Parent Navigation Name
                NULL,                   // Index
                NULL,                   // Description
                NULL,                   // Bootstrap Glyph Class
                NULL,                   // Default Theme
                'default-one-column',   // Default Layout
                NULL,                   // Notification URL Path
                0,                      // Hidden
                ''                      // Static Content
            );
            
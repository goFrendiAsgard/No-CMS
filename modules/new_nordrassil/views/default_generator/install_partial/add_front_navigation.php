        // {{ front_navigation_name }}
        $this->cms_add_navigation(
                $this->n('{{ front_navigation_name }}'),  //  Navigation name
                'Browse {{ table_caption }}',  // Title
                $module_path.'/{{ front_controller_name }}',  // URL Path 
                PRIV_EVERYONE,   // Authorization
                $this->n('{{ navigation_parent_name }}'),  // Parent Navigation Name
                NULL, // Index
                NULL, // Description
                NULL, // Bootstrap Glyph Class
                NULL, // Default Theme
                NULL, // Default Layout
                NULL, // Notification URL Path
                0,    // Hidden
                ''    // Static Content
            );
        
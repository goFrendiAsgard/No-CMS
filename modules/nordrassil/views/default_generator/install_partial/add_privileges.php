        // {{ stripped_table_name }}
        $this->cms_add_privilege($this->cms_complete_navigation_name('read_{{ stripped_table_name }}'),          'read {{ stripped_table_name }}');
        $this->cms_add_privilege($this->cms_complete_navigation_name('add_{{ stripped_table_name }}'),           'add {{ stripped_table_name }}');
        $this->cms_add_privilege($this->cms_complete_navigation_name('edit_{{ stripped_table_name }}'),          'edit {{ stripped_table_name }}');
        $this->cms_add_privilege($this->cms_complete_navigation_name('delete_{{ stripped_table_name }}'),        'delete {{ stripped_table_name }}');
        $this->cms_add_privilege($this->cms_complete_navigation_name('list_{{ stripped_table_name }}'),          'list {{ stripped_table_name }}');
        $this->cms_add_privilege($this->cms_complete_navigation_name('back_to_list_{{ stripped_table_name }}'),  'back to list {{ stripped_table_name }}');
        $this->cms_add_privilege($this->cms_complete_navigation_name('print_{{ stripped_table_name }}'),         'print {{ stripped_table_name }}');
        $this->cms_add_privilege($this->cms_complete_navigation_name('export_{{ stripped_table_name }}'),        'export {{ stripped_table_name }}');

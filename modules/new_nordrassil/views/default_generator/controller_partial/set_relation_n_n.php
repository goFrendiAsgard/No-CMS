        $crud->set_relation_n_n('{{ field_name }}',
            $this->t('{{ relation_table_name }}'),
            $this->t('{{ selection_table_name }}'),
            '{{ relation_table_field_name }}', '{{ relation_selection_field_name }}',
            '{{ selection_field_name }}', {{ relation_priority_field_name }});
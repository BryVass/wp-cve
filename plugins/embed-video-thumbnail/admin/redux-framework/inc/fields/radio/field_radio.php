<?php

// Exit if accessed directly
    if (!\defined('ABSPATH')) {
        exit;
    }

    if (!class_exists('ReduxFramework_radio')) {
        class ReduxFramework_radio
        {
            /**
             * Field Constructor.
             * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function.
             *
             * @since ReduxFramework 1.0.0
             *
             * @param mixed $field
             * @param mixed $value
             * @param mixed $parent
             */
            public function __construct($field = [], $value = '', $parent)
            {
                $this->parent = $parent;
                $this->field = $field;
                $this->value = $value;
            }

            /**
             * Field Render Function.
             * Takes the vars and outputs the HTML for the field in the settings.
             *
             * @since ReduxFramework 1.0.0
             */
            public function render()
            {
                if (!empty($this->field['data']) && empty($this->field['options'])) {
                    if (empty($this->field['args'])) {
                        $this->field['args'] = [];
                    }
                    $this->field['options'] = $this->parent->get_wordpress_data($this->field['data'], $this->field['args']);
                }

                $this->field['data_class'] = (isset($this->field['multi_layout'])) ? 'data-' . $this->field['multi_layout'] : 'data-full';

                if (!empty($this->field['options'])) {
                    echo '<ul class="' . $this->field['data_class'] . '">';

                    foreach ($this->field['options'] as $k => $v) {
                        echo '<li>';
                        echo '<label for="' . $this->field['id'] . '_' . array_search($k, array_keys($this->field['options'])) . '">';
                        echo '<input type="radio" class="radio ' . $this->field['class'] . '" id="' . $this->field['id'] . '_' . array_search($k, array_keys($this->field['options'])) . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '" value="' . $k . '" ' . checked($this->value, $k, false) . '/>';
                        echo ' <span>' . $v . '</span>';
                        echo '</label>';
                        echo '</li>';
                    }
                    //foreach

                    echo '</ul>';
                }
            }

            //function
        } //class
    }

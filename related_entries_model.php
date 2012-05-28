<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	require_once(FUEL_PATH . 'models/base_module_model.php');

	class Related_entries_model extends Base_module_model
	{
		public $record_class = 'Related_entry';
		// Which models we can link to this one in a one_to_many way
		public $related_models; // array

		// used for the FUEL admin
		function list_items($limit = NULL, $offset = NULL, $col = 'id', $order = 'desc')
		{
			$data = parent::list_items($limit, $offset, $col, $order);
			return $data;
		}

		function form_fields($values = array())
		{
			$fields = parent::form_fields();
			// Get all the possible values for related entries (only during editing)
			if (count($this->related_models)) {
				$related_values = array();
				if (isset($values['id'])) {
					$where           = array('entry_id'    => $values['id'],
					                         'entry_model' => strtolower(get_class($this)));

					$related_entries = $this->db->select()->from(RELATED_ENTRIES_TABLE)->where($where)->get();
					if (count($related_entries)) {
						foreach ($related_entries->result() as $related_entry) {
							$related_values[] = $related_entry->related_model . '#' . $related_entry->related_id;
						}
					}
				}

				$fields['related_entries'] = array(
					'type'  => 'multi',
					'label' => 'Related entries',
					'class' => 'no_combo',
					'mode'  => 'select',
					'value' => $related_values
				);
				$options                   = array();
				// Get all entries from each model as select options
				foreach ($this->related_models as $model) {
					$this->load->module_model('gocart', $model);
					$entries                              = $this->$model->my_options_list();
					$options[$this->$model->short_name()] = $entries;
				}
				$fields['related_entries']['options'] = $options;
			}
			return $fields;
		}

		function my_options_list($key = NULL, $val = NULL, $where = array(), $order = TRUE)
		{
			$return  = array();
			$entries = $this->find_all();
			foreach ($entries as $entry) {
				$return[$this->short_name(TRUE).'_model#'.$entry->id] = $entry->title;
			}
			return $return;
		}

		function on_after_save($values)
		{
			$saved_data = $this->normalized_save_data;
			// Clear the related_entries DB first
			$this->db->delete(RELATED_ENTRIES_TABLE, array('entry_id'    => $values['id'],
			                                               'entry_model' => strtolower(get_class($this))));
			// Save the related entries
			if (isset($saved_data['related_entries'])) {
				$related_entries = $saved_data['related_entries'];

				if (count($related_entries)) {
					foreach ($related_entries as $related_entry) {
						list($related_model, $related_id) = explode('#', $related_entry);
						$this->db->insert('gc_related_entries', array(
							'entry_id'      => $values['id'],
							'entry_model'   => strtolower(get_class($this)),
							'related_id'    => $related_id,
							'related_model' => $related_model
						));
					}
				}
			}
		}
	}

	class Related_entry_model extends Base_module_record
	{
		/**
		 * TODO
		 * Returns an array of related entries, divided by model
		 */
		function get_related_entries()
		{
			$related = array();
			// ...
			return $related;
		}

	}
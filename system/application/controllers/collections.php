<?php
class Collections extends Controller
{
	function Collections() {
		parent::Controller();
	}

	/**
	 * Handler method for the base page
	 */
	function read($id) {
		$this->load->model('Collection');
		$output = $this->Collection->read($id);
		$this->load->view('collections', $output);
	}

	function find($query = null) {
		if ($query == null) {
			$query = '#';
		}
		$this->load->model('Collection');
		$output = $this->Collection->findByStartsWith($query);
		$this->load->view('collections', $output);
	}
}

/* End of file collections.php */
/* Location: ./system/application/controllers/collections.php */
?>
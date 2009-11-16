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
		$data = $this->Collection->read($id);
		$this->load->view('collections', $data);
	}

	function find($query = null) {
		if ($query == null) {
			$query = '#';
		}
		$this->load->model('Collection');
		$data = $this->Collection->findByStartsWith($query);
		$this->load->view('collections', $data);
	}
}

/* End of file collections.php */
/* Location: ./system/application/controllers/collections.php */
?>
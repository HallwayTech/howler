<?php
class Collections extends Controller
{
	function Collections() {
		parent::Controller();
	}

	/**
	 * Handler method for the base page
	 */
	function byParent($id)
	{
		$this->load->model('Collection');
		$data = $this->Collection->byParent($id);
		$this->load->view('collections', $data);
	}

	function find($query = null)
	{
		$this->load->model('collection');
		$data = $this->collection->startsWith($query);
		$this->load->view('collections', $data);
	}
}

/* End of file collections.php */
/* Location: ./system/application/controllers/collections.php */

package howler

class AlbumController {
	def list = {
		//        params.max = Math.min(params.max ? params.int('max') : 10, 100)
		[entries:Entry.list(params), entriesCount:Entry.count()]
	}

	def show = {
		
	}
}

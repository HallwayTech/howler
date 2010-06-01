package howler

class AlbumController {
	def list = {
        params.max = Math.min(params.max ? params.int('max') : 10, 100)
		def entries = Entry.withCriteria {
			cache false
			projections {
				groupProperty 'album'
				rowCount()
			}
			order 'album'
		}
		render(view:'../entry/list', model: [entries:entries])
	}

	def search = {
		params.max = Math.min(params.max ? params.int('max') : 10, 100)
		def entries = Entry.withCriteria {
			cache false
			if (params.album) {
				eq 'album', params.album
			}
			order 'album'
		}
		[entries:entries]
	}
}


class Entry {
	String id
	String album
	String track
	String artist
	String title
	String year
	String genre
	String path

	static mapping = {
		id generator: 'uuid'
	}

  static constraints = {
		track nullable: true
		artist blank: false
		title blank: false
		year nullable: true
		genre nullable: true
  }
}

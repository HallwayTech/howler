package howler

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
		artist blank: false, index: 'Artist_Idx'
		album index: 'Album_Idx'
		title blank: false
		year nullable: true
		genre nullable: true
	}
}

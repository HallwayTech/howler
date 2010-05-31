package howler

class Entry {
	String artist
	String album
	String title
	String track
	
    static constraints = {
		artist(blank: false)
		title(blank: false)
    }
}

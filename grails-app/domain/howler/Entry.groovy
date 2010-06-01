package howler

class Entry {
	String album
	String track
	String artist
	String title

    static constraints = {
		track(nullable: true)
		artist(blank: false)
		title(blank: false)
    }
}

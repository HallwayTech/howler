import groovy.sql.Sql

@Grapes([
	@Grab('mysql:mysql-connector-java:5.1.6'),
	@GrabConfig(systemClassLoader=true)
])
def sql = Sql.newInstance('jdbc:mysql://localhost/howler', 'root', 'mtrpls12', 'com.mysql.jdbc.Driver')
sql.eachRow("select id from entry e inner join (select path from entry group by path having count(*) > 1) as en on e.path = en.path group by e.path") {
	sql.execute("delete from entry where id = ?", [it[0]])
	println "deleted ${it[0]}"
}

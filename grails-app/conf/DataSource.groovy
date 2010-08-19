dataSource {
	pooled = true
}
hibernate {
    cache.use_second_level_cache = true
    cache.use_query_cache = true
    cache.provider_class = 'net.sf.ehcache.hibernate.EhCacheProvider'
}
// environment specific settings
environments {
	development {
		dataSource {
			dbCreate = 'create-drop' // one of 'create', 'create-drop','update'
			driverClassName = 'org.hsqldb.jdbcDriver'
			url = 'jdbc:hsqldb:mem:devDB'
			username = 'sa'
			password = ''
		}
	}
	test {
		dataSource {
			dbCreate = 'update'
			driverClassName = 'com.mysql.jdbc.Driver'
			url = 'jdbc:mysql://localhost/howler'
			username = 'root'
			password = 'mtrpls12'
		}
	}
	production {
		dataSource {
			dbCreate = 'update'
			url = 'jdbc:hsqldb:file:prodDb;shutdown=true'
		}
	}
}
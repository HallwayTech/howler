var Template = function()
{
	return {
		// cache of templates for processing
		templateCache: {},

		// cache of rendered output
		outputCache: {},

		/**
		 * Processes a template using the given data.  Caches the template if not used
		 * before.  If cacheIdx is provided, the output is cached under that arbitrary
		 * index.
		 */
		processTemplate: function(templateId, json, cacheIdx) {
		alert($(templateId).html());
			if (!Template.templateCache[templateId]) {
			     alert('template1');
			     template = TrimPath.parseDOMTemplate(templateId);
			     alert('template2');
				Template.templateCache[templateId] = template;
			}

			var output = Template.templateCache[templateId].process(json);
			alert(output);
			if (cacheIdx) {
				Template.outputCache[cacheIdx] = output;
			}
			return output;
		},

		clearCaches: function() {
			Template.templateCache = {};
			Template.outputCache = {};
		}
	}
}();

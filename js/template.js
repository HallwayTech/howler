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
			if (!Template.templateCache[templateId]) {
				Template.templateCache[templateId] = TrimPath.parseDOMTemplate(templateId);
			}

			var output = Template.templateCache[templateId].process(json);
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

module EntryHelper
  # Renders JavaScript to have jQuery call to the server.
  #
  # *controller*:: Controller to call to. See +url_for+.
  # *action*:: Action to call to. See +url_for+.
  # params:: Parameters (data) to send with the call.
  # update:: HTML entity to update. Accepts a jQuery selector.
  def remote_function(args)
    url = url_for(:controller => args[:controller], :action => args[:action])
    params = ", #{ActiveSupport::JSON.encode(args[:params])}" if args[:params]
    update = ", function(data) {$('#{args[:update]}').html(data)}" if args[:update]
    "$.get('#{url}'#{params}#{update})"
  end
end

require 'test_helper'

class EntryControllerTest < ActionController::TestCase
  test "should get findAllBy" do
    get :findAllBy
    assert_response :success
  end

end

import FormDataFactory from '../FormDataFactory.js';

import Auth from './Auth.js';
import Category from './Category.js';
import Config from './Config.js';
import User from './User.js';
import Log from './Log.js';
import PermissionGroup from './PermissionGroup.js';
import Report from './Report.js';
import Profile from './Profile.js';

export default function ApiManager($http, $cookie) {
  // var apiToken = $cookie.get('api_token');
  var vm = this;

  this.setApiToken = function(newApiToken) {
    $cookie.set('api_token', newApiToken);
    $cookie.set('is_first_login', 1);
    // apiToken = $cookie.get('api_token');
  };

  this.unsetApiToken = function()
  {
      $cookie.set('api_token', '');
  }

  this.getApiToken = function() {
      return $cookie.get('api_token');
  };

  // ===============================
  // HTTP Request Shorthand(START)
  // ===============================
  // To send GET request
  this.get = function(url, data) {
      var _apiToken = this.getApiToken();

      return $http.get(url, {
          headers: { 'Authorization': 'Bearer ' +  _apiToken }
      });
  };

  this.post = function(url, data) {
      var _apiToken = this.getApiToken();

      return $http({
          method: 'POST',
          url:url,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Authorization': 'Bearer ' +  _apiToken
          },
          data: $.param(data)
      });
  };

  this.upload = function(url, data) {
      var _apiToken = this.getApiToken();

      var formData = new FormData();
      formData.append('file', data.file);

      delete data.file;

      formData.append('data', JSON.stringify(data));

      return $http({
          method: 'POST',
          url:url,
          headers: {
            'Content-Type': 'multipart/form-data',
            'Authorization': 'Bearer ' +  _apiToken
          },
          data: formData
      });
  };

  this.multiple = function(url, data) {
    var _apiToken = this.getApiToken();

    var formData = new FormData();
    for( var i = 0; i < data.file.length; i++ ){
        formData.append('files[' + i + ']', data.file[i]);
    }

    delete data.file;

    formData.append('data', JSON.stringify(data));

    return $http({
        method: 'POST',
        url:url,
        headers: {
          'Content-Type': 'multipart/form-data',
          'Authorization': 'Bearer ' +  _apiToken
        },
        data: formData
    });
  };

  this.multipleV2 = function(url, data) {
    var _apiToken = this.getApiToken();

    var formData = FormDataFactory.toFormData(data.files, 'files');

    delete data.files;

    formData.append('data', JSON.stringify(data));

    return $http({
        method: 'POST',
        url:url,
        headers: {
          'Content-Type': 'multipart/form-data',
          'Authorization': 'Bearer ' +  _apiToken
        },
        data: formData
    });
  };

  this.delete = function(url) {
      var _apiToken = this.getApiToken();

      return $http.delete(url, {
          headers: { 'Authorization': 'Bearer ' +  _apiToken }
      });
  };

  // ===============================
  // HTTP Request Shorthand(END)
  // ===============================

  this.Auth = new Auth(this);
  this.Category = new Category(this);
  this.Config = new Config(this);
  this.User = new User(this);
  this.Log = new Log(this);
  this.PermissionGroup = new PermissionGroup(this);
  this.Report = new Report(this);
  this.Profile = new Profile(this);
};

(function (global) {
  'use strict';

  var cfg = function () {
    return global.PORTAIL_CONFIG || { apiBase: '/api/v1' };
  };

  function apiBase() {
    return String(cfg().apiBase || '').replace(/\/$/, '');
  }

  function apiUrl(path) {
    var p = String(path || '').replace(/^\//, '');
    return apiBase() + '/' + p;
  }

  function getToken() {
    try {
      return sessionStorage.getItem('portail_api_token') || '';
    } catch (e) {
      return '';
    }
  }

  function setToken(token) {
    try {
      if (token) {
        sessionStorage.setItem('portail_api_token', token);
      } else {
        sessionStorage.removeItem('portail_api_token');
      }
    } catch (e) { /* ignore */ }
  }

  function ajaxJson(method, path, data, extraHeaders) {
    var headers = $.extend({ Accept: 'application/json' }, extraHeaders || {});
    var token = getToken();
    if (token) {
      headers.Authorization = 'Bearer ' + token;
    }
    return $.ajax({
      url: apiUrl(path),
      method: method,
      data: data,
      dataType: 'json',
      headers: headers,
    });
  }

  global.PortailApi = {
    apiUrl: apiUrl,
    getToken: getToken,
    setToken: setToken,
    post: function (path, data) {
      return ajaxJson('POST', path, data);
    },
    get: function (path, data) {
      return ajaxJson('GET', path, data);
    },
  };
})(window);

/**
 * How to use:
 * include js at onHead
 * add <tr-paging public="pager"></tr-paging> at HTML
 * important vars: pager.uri, pager.totalItems
 * important vars: pager.filterOptions, pager.orderOptions each is array of {key: text: }
 * optional var: pager.moreParams --> the properties of this object will be sent at every page changes
 * pager.pageChanged(rep) will be called when server finished responding.
 * pager.changePage(1) can change page from parent controller
 * set pager.filterBy and pager.orderBy before calling pager.changePage
 * Serverside:
 * -- to server: currentPage, itemsPerPage, filterBy, orderBy, moreParams
 * -- from server: rep.currentPage, rep.itemsPerPage, rep.totalItems, rep.anything
 */
app.directive("trPaging", function () {
  var path = $('script[src$="DirPaging2.js"]').attr('src');
  var mydir = path.split('/').slice(0, -1).join('/') + '/';  // remove last filename part of path
  return {
    templateUrl: mydir + "DirPaging2.html",
    restrict: 'E',
    //scope: {totalItems: "=totalitems", uri: "@", filterOptions: "=filters", orderOptions: "=orders"},
    scope: {public:"="},
    link: function (scope, element) {
      scope.public.currentPageInput = 1;
      scope.public.itemsPerPageInput = 50;
      scope.public.filterBy = [];
      scope.public.orderBy = [];
      //public.filterOptions, public.orderOptions
      
      //Private Vars
      scope.currentPage = 1;
      scope.itemsPerPage = 50;
      scope.itemsPerPageOptions = [10, 25, 50, 100, 200, 500];
      //automatic vars
      scope.totalPage = 0;
      scope.pageLinks = [];

      //setPages: based on params, automatically set totalPage(pagecount), pageLinks
      scope.public.setPages = function (rep) {
        scope.currentPage = +rep.currentPage;
        scope.itemsPerPage = +rep.itemsPerPage;
        scope.public.totalItems = +rep.totalItems;
        scope.totalPage = Math.ceil(scope.public.totalItems / scope.itemsPerPage);
        scope.pageLinks = [];
        var page = (scope.currentPage === 1) ? 4 : scope.currentPage; //TODO: refactor
        for (var i = page - 2; i <= page + 2; i++) {
          if (i > 1 && i < scope.totalPage) scope.pageLinks.push(i);
        }
        scope.currentPageInput = scope.currentPage;
        scope.public.itemsPerPageInput = scope.itemsPerPage;
      };
      //Set initial loaded values.
      scope.public.setPages({currentPage:1, itemsPerPage:50, totalItems:scope.public.totalItems});
      //computedProperty: The starting record index of current Page
      scope.startRecord = function () {
        if (scope.public.totalItems === 0) return 0;
        return (scope.currentPage - 1) * scope.itemsPerPage + 1;
      };
      //computedProperty: The last record index of current Page
      scope.endRecord = function () {
        if (scope.currentPage >= scope.totalPage) return scope.public.totalItems;
        return scope.currentPage * scope.itemsPerPage;
      };

      //Filter UI Functions
      scope.addFilter = function (f, e) { //f:filter, e:unused
        for (var i in scope.public.filterBy) {
          if (f.key === scope.public.filterBy[i].key) return;
        }
        scope.public.filterBy.push({key: f.key, text: f.text, query: ""});
      };
      scope.removeFilter = function (f) {
        scope.public.filterBy.splice(scope.public.filterBy.indexOf(f), 1);
      };
      //Sort UI Functions
      scope.addSort = function (s) { //s:sort, e:unused
        for (var i in scope.public.orderBy) {
          if (s.key === scope.public.orderBy[i].key) return;
        }
        scope.public.orderBy.push({key: s.key, text: s.text, dir: "ASC"});
      };
      scope.reverseSort = function (s) {
        if (s.dir === "ASC") s.dir = "DESC";
        else s.dir = "ASC";
      };
      scope.removeSort = function (s) {
        this.public.orderBy.splice(this.public.orderBy.indexOf(s), 1);
      };
      //Change page. By search button or paging button
      if (scope.public.changePage == undefined) //the changePage function is overrideable
        scope.public.changePage = function (pageNum) { //set moreParams at parent for additional search parameter e.g dates
        var pager = {
          currentPage: pageNum,
          itemsPerPage: scope.public.itemsPerPageInput,
          filterBy: scope.public.filterBy,
          orderBy: scope.public.orderBy
        };
        var oPost = {a: "get", pager: pager, token:token};
        if (scope.public.moreParams != undefined) {
          for (var i in scope.public.moreParams) oPost[i] = scope.public.moreParams[i];
        }
        tr.post(scope.public.uri, oPost, function (rep) {
          scope.public.setPages(rep);
          if (scope.public.pageChanged != undefined) scope.public.pageChanged(rep);
        });
      };
      //When filter and sort modified, the search start from first page.
      scope.newSearch = function () {
        scope.public.changePage(1);
      };
    }
      //When parent controller want to change page manually, use public.changePage a.k.a pager.changePage
      //To respond to page changes, use public.pageChanged(rep) a.k.a pager.pageChanged(rep)
  };
});

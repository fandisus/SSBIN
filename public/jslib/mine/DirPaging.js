/**
 * How to use:
 * include js at onHead and Inject to controller,
 * add <tr-paging totalitems="" uri="anu.php" filters="" orders=""></tr-paging>
 * at related js: 
 * vars: totalItems, filters (SearchCriteria) and orders(OrdersColumns)  --> each is array of {key: text: }
 * $scope.$broadcast("setPage", {curPage:1, perPage:50, totalItems:rep.totalItems}); on changeUMH
 * $scope.$on("pageChanged", function(e, rep) {}); assign data, empty selections, $apply(), reflow()
 * Serverside:
 * -- Params sent: currentPage, itemsPerPage, filters, sorts
 * -- Params to reply: rep.currentPage, rep.itemsPerPage, rep.totalItems
 * -- additional params handled at maincontroller's $scope.$on("pageChanged",function(e,rep){})
 */
app.directive("trPaging", function() {
    var script= document.getElementById('trLoading'); //Tag pemanggil javascript
    var path= script.src.split('?')[0];      // remove any ?query
    var mydir= path.split('/').slice(0, -1).join('/')+'/';  // remove last filename part of path
    return {
        restrict: 'E',
        scope: {totalItems:"=totalitems", uri:"@", filterOptions:"=filters", orderOptions:"=orders"},
        link: function(scope, element) {
            //Input Vars: Has Input Forms
            scope.currentPageInput=1;
            scope.itemsPerPageInput=50;

            //Private Vars
            scope.currentPage = 1;
            scope.itemsPerPage = 50;
            scope.itemsPerPageOptions = [10,25,50,100];
            scope.totalPage = 0;
            scope.pageLinks = [];
            scope.filterBy = [];
            scope.orderBy=[];
            
            //Unknown usage: TODO: Review
//            scope.$on("setPage", function(e, p) {
//                if (p.target !== scope.uri) return;
//                scope.setPages(p.curPage, p.perPage, p.totalItems);
//            });
            //setPages: based on params, automatically set totalPage(pagecount), pageLinks
            scope.setPages = function(curPage, perPage, totalItems) {
                scope.currentPage = curPage;
                scope.itemsPerPage = perPage;
                scope.totalItems = totalItems;
                scope.totalPage = Math.ceil(totalItems/perPage);
                scope.pageLinks = [];
                if (curPage === 1) page = 4; else page=curPage;
                for (var i=page-2; i<=page+2; i++) {
                    if (i>1 && i<scope.totalPage) scope.pageLinks.push(i);
                }
                scope.currentPageInput = scope.currentPage;
                scope.itemsPerPageInput = perPage;
            };
            //Set initial loaded values.
            scope.setPages(1,50, scope.totalItems);
            //computedProperty: The starting record index of current Page
            scope.startRecord = function() {
                if (scope.totalItems === 0) return 0;
                return (scope.currentPage-1)*scope.itemsPerPage + 1; 
            };
            //computedProperty: The last record index of current Page
            scope.endRecord = function() {
                if (scope.currentPage >= scope.totalPage) return scope.totalItems;
                return scope.currentPage * scope.itemsPerPage;
            };

            //Filter UI Functions
            scope.addFilter = function(f, e) { //f:filter, e:unused
                for (var i in scope.filterBy) {if (f.key === scope.filterBy[i].key) return; }
                scope.filterBy.push({key:f.key, text:f.text, query:""});
            };
            scope.removeFilter = function(f) {
                scope.filterBy.splice(scope.filterBy.indexOf(f),1);
            };
            //Sort UI Functions
            scope.addSort = function(s) { //s:sort, e:unused
                for (var i in scope.orderBy) {if (s.key === scope.orderBy[i].key) return; }
                scope.orderBy.push({key:s.key, text:s.text, dir:"ASC"});
            };
            scope.reverseSort = function(s) {
                if (s.dir === "ASC") s.dir = "DESC"; else s.dir="ASC";
            };
            scope.removeSort = function(s) {
                this.orderBy.splice(this.orderBy.indexOf(s),1);
            };
            //Change page. By search button or paging button
            scope.changePage = function(pageNum) {
                var pager = {
                    currentPage:pageNum, 
                    itemsPerPage: scope.itemsPerPageInput, 
                    filterBy:scope.filterBy,
                    orderBy: scope.orderBy
                };
                var oPost={a:"getDatas", pager:pager};
                tr.post(scope.uri, oPost, function(rep) {
                    scope.setPages(+rep.currentPage, +rep.itemsPerPage, +rep.totalItems);
                    scope.$emit("pageChanged", rep);
                });
            };
            //When filter and sort modified, the search start from first page.
            scope.newSearch = function() {
                scope.changePage(1);
            };
            //When parent controller want to change page manually.
            scope.$on("pagerSearch", function(e, p) {
              scope.filterBy=p.filterBy;
              scope.orderBy=p.orderBy;
              scope.changePage(1);
            });
        },
        templateUrl:mydir+"temp-pager.html"
    };
});

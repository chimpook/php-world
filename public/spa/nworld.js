;"use strict";

/**
 * NWorld module
 * (SPA Viewer of the World's Regions direcory)
 */
(function() {

    function NWorld(apiUrlRoot) {
        this.apiUrlRoot = apiUrlRoot;
    }

    NWorld.prototype = {
        
        apiUrlRoot: '',
        data: {},
        e: {},
        nwTitle: {},
        nwBack: {},
        nwAPI: {},
        nwTable: {},

        /**
         * Load JSON data from the Back-end API
         * 
         * @param string url 
         * @param {Function} callback 
         */
        ajax: function(url, callback) {
            let xhr = new XMLHttpRequest();
            xhr.open('GET', url);
            xhr.onreadystatechange = function() {
                if (this.readyState == 4) {
                    if (this.status == 200) {
                        callback(JSON.parse(this.responseText));
                    } else {
                        console.log('Network  error!');
                    }
                }
            };
            xhr.send(null);
        },

        /**
         * Build the DOM structure
         * #container
         *   #nwTitle   - Title of the page
         *   #nwBack    - Link to the previous page
         *   #nwAPI     - Link to JSON API of the current page
         *   #nwTable   - Data table
         *     #nwThead - Header of the table with sorting headers
         *     #nwTbody - Data part of the table with links to the relating pages
         */
        build: function() {

            // Create the container's element
            this.e = document.createElement('div');
            this.e.setAttribute('id', 'nwContainer');
            this.e.setAttribute('class', 'container');
            
            // Create the title element
            this.nwTitle = document.createElement('div');
            this.nwTitle.setAttribute('id', 'nwTitle');
            this.setTitle();
            this.e.appendChild(this.nwTitle);

            // Create the back element
            this.nwBack = document.createElement('div');
            this.nwBack.setAttribute('id', 'nwBack');
            this.nwBack.setAttribute('class', 'float-left');
            this.setBack();
            this.e.appendChild(this.nwBack);

            // Create the API element
            this.nwAPI = document.createElement('div');
            this.nwAPI.setAttribute('id', 'nwAPI');
            this.nwAPI.setAttribute('class', 'float-right');
            this.setAPI();
            this.e.appendChild(this.nwAPI);

            // Create the Table element
            this.nwTable = document.createElement('div');
            this.nwTable.setAttribute('id', 'nwTable');
            this.setTable();
            this.e.appendChild(this.nwTable);

            this.setSortListeners();
            this.setJumpListeners();
            this.setBackListeners();
            //console.log(this.e);
        },

        /**
         * Set title with h2 header
         */
        setTitle: function() {
            this.nwTitle.innerHTML = '<h2>' + this.data.title + '</h2>';
        },

        /**
         * Set parameters of the "Back" link
         */
        setBack: function() {
            if (this.data.back && this.data.title != "Regions of the World") {
                this.nwBack.innerHTML = '<span class="fa fa-angle-double-left"></span>'
                + '<span class="doBack" data-url="' + this.data.back.url + '">'
                + this.data.back.title + '</span>';
            } else {
                this.nwBack.innerHTML = '';
            }
        },

        /**
         * Set API link
         */
        setAPI: function() {
            this.nwAPI.innerHTML = '<a class="doAPI" href="' + this.data.apiUrl + '">[ API ]</a>';
        },

        /**
         * Set data Table
         */
        setTable: function() {
            let self = this;
            let c = '<table class="table">'

            // Set Thead
            c += '<tr>';
            self.data.columns.forEach(function(column) {
                c += '<th>';
                if (self.data.sort.replace('SortBy', '') === column.field) {
                    if (self.data.dir.toUpperCase() === 'ASC') {
                        c += '<span class="fa fa-angle-down"></span>'
                        + '<span class="doSort" data-sort="SortBy' + column.field 
                        + '" data-dir="Desc">' + column.title + '</span>';
                    } else {
                        c += '<span class="fa fa-angle-up"></span>'
                        + '<span class="doSort" data-sort="SortBy' + column.field 
                        + '" data-dir="Asc">' + column.title + '</span>';
                    }
                } else {
                    c += '<span class="fa fa-angle-down hidden"></span>'
                    + '<span class="doSort" data-sort="SortBy' + column.field 
                    + '" data-dir="Asc">' + column.title + '</span>';
            }
                c += '</th>';
            });
            c += '</tr>';

            // Set Tbody
            self.data.list.forEach(function(row) {
                c += '<tr>';
                self.data.columns.forEach(function(col) {
                    c += '<td>';
                    if (col.link) {
                        c += '<span class="doJump" data-template="' + col.link.template 
                            +'" data-filter="'+ row[col.link.filter] +'">'
                            + row[col.field] + '</span>'; 
                    } else {
                        c += row[col.field];
                    }
                    c += '</td>';
                });
                c += '</tr>';
            });

            c += '</table>';
            self.nwTable.innerHTML = c;
        },

        /**
         * Set sorting listeners
         */
        setSortListeners: function() {
            let self = this;
            // Add event listeners to sorting headers
            let sorters = this.e.getElementsByClassName("doSort");
            for (let i=0; i < sorters.length; i++) {
                sorters[i].addEventListener('click', function() {
                    let sort = this.getAttribute('data-sort');
                    let dir = this.getAttribute('data-dir');
                    let type = self.getType(sort);
                    self.sortList(sort, type, dir);
                }, false);
            }
        },

        /**
         * Set listeners on links to related pages
         */
        setJumpListeners: function() {
            let self = this;
            // Add event listeners to jumping cells
            let jumpers = this.e.getElementsByClassName("doJump");
            //console.log(jumpers);
            for (let i=0; i < jumpers.length; i++) {
                jumpers[i].addEventListener('click', function() {
                    let template = this.getAttribute('data-template');
                    let filter = this.getAttribute('data-filter');
                    let url = self.apiUrlRoot + template + '/' + filter;
                    //console.log(url);
                    self.ajax(url, function(data) {
                        //console.log(data);
                        self.data = data;
                        self.setTitle();
                        self.setBack();
                        self.setAPI();
                        self.setTable();
                        self.setSortListeners();
                        self.setJumpListeners();
                        self.setBackListeners();
                    });
                }, false);
            }
        },

        /**
         * Set "back" listeners
         */
        setBackListeners: function() {
            let self = this;
            // Add event listeners to back switchers
            let backs = this.e.getElementsByClassName("doBack");
            for (let i=0; i < backs.length; i++) {
                backs[i].addEventListener('click', function() {
                    let url = this.getAttribute('data-url');
                    self.ajax(url, function(data) {
                        //console.log(data);
                        self.data = data;
                        self.setTitle();
                        self.setBack();
                        self.setAPI();
                        self.setTable();
                        self.setSortListeners();
                        self.setJumpListeners();
                        self.setBackListeners();
                    });
                }, false);
            }
        },

        /**
         * Get type of sorting field
         * 
         * @param {string} sort 
         */
        getType: function(sort) {
            let sortField = sort.replace('SortBy', '');
            for (let i=0; i < this.data.columns.length; i++) {
                if (this.data.columns[i].field == sortField) {
                    return this.data.columns[i].type;
                }
            }
        },

        /**
         * Sort data list
         * (three types - enum, string and numbers)
         * 
         * @param {string*} sort 
         * @param {string} type 
         * @param {string} dir 
         */
        sortList: function(sort, type, dir) {
            let self = this;
            let sortField = sort.replace('SortBy', '');
            if (type === 'string') {
                self.data.list.sort(function(a, b) {
                    if(dir.toUpperCase() === 'ASC') {
                        return a[sortField].localeCompare(b[sortField]);
                    } else {
                        return b[sortField].localeCompare(a[sortField]);
                    }
                });
            } else if (type === 'enum') {
                self.data.list.sort(function(a, b) {
                    let aEnum = self.data.enums[sortField].indexOf(a[sortField]);
                    let bEnum = self.data.enums[sortField].indexOf(b[sortField]);
                    if(dir.toUpperCase() === 'ASC') {
                        if (aEnum > bEnum) {
                            return 1;
                        }
                        if (aEnum < bEnum) {
                            return -1;
                        }
                        return 0;
                    } else {
                        if (aEnum < bEnum) {
                            return 1;
                        }
                        if (aEnum > bEnum) {
                            return -1;
                        }
                        return 0;
                    }
                });
            } else {
                self.data.list.sort(function(a, b) {
                    let aNum = Number(a[sortField]);
                    let bNum = Number(b[sortField]);
                    if(dir.toUpperCase() === 'ASC') {
                        if (aNum > bNum) {
                            return 1;
                        }
                        if (aNum < bNum) {
                            return -1;
                        }
                        return 0;
                    } else {
                        if (aNum < bNum) {
                            return 1;
                        }
                        if (aNum > bNum) {
                            return -1;
                        }
                        return 0;
                    }
                });
            }
            self.data.sort = sort;
            self.data.dir = dir;
            self.setAPI();
            self.setTable();
            self.setSortListeners();
            self.setJumpListeners();
        },

    };

    window.NWorld = NWorld;
}());


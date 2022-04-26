;"use strict";

/**
 * Application module
 */
let app = {

    nw: new NWorld('http://localhost:8081/api/'),

    /**
     * Load data to the module
     * @param {Object} data 
     */
    load: function(data) {
        this.nw.data = data;
    },

    /**
     * Buld the content structure
     */
    build: function() {
        this.nw.build();
    },

    /**
     * Output content - append it to the assigned container
     * (div with id="nworld")
     * @param {Element} world 
     */
    output: function(world) {
        world.appendChild(this.nw.e);
    }
};

document.addEventListener("DOMContentLoaded", function(){
    let nworld = document.getElementById("nworld");
    /**
     * Load data with ajax and initialize the application
     */
    app.nw.ajax(app.nw.apiUrlRoot, function(data) {
        app.load(data);
        app.build();
        app.output(nworld);
    });
});

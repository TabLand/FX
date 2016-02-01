//supress errors
phantom.onError = function(msg,trace){}

var page = require('webpage').create();
 
page.onResourceError = function(resourceError) {
    page.reason = resourceError.errorString;
    page.reason_url = resourceError.url;
};
 
page.open(
    "http://www.xe.com/currencycharts/?from=GBP&to=PKR",
    function (status) {
        if ( status !== 'success' ) {
            console.log(
                "Error opening url \"" + page.reason_url
                + "\": " + page.reason
            );
            phantom.exit( 1 );
        } else {
            console.log(page.content);
            phantom.exit( 0 );
        }
    }
);

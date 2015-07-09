var baseUrl = 'https://api.500px.com/v1/';
var searchEndPoint = 'photos/search';

jQuery('#widget-insp_widget').on('click', function() {
    //get parameters from element attributes
    var attributes = this.getAttribute( "data-options" );
    var parameters = attributes.split(",");
    console.log(parameters);

    //determine what to search
    var fullUrl = baseUrl + searchEndPoint + '?consumer_key=dPDkJA7iy5mKFDswMGvdDaEwT02CD94E38D9rtRF';
    fullUrl = fullUrl + '&tag=' + parameters[0];
    fullUrl = fullUrl + '&image_size=440'; //21
    console.log(fullUrl);
    //actually do search and process result
    jQuery.get(fullUrl, function(data, status) {
        console.log("return data is: ");
        console.log(data);
        var randomIndex = Math.floor(Math.random()*20);
        lightBoxPopup(data.photos[randomIndex]);
    });

});

//make lightbox style popup
function lightBoxPopup(photoDetails) {
    var desc = photoDetails.name;
    var url = photoDetails.image_url;
    var popup = jQuery('#nature_box');
    popup.find('.description').text(desc);
    popup.find('.image_holder').attr("src", url);
    popup.show();
}

jQuery('#nature_box .close').on('click', function() {
    jQuery('#nature_box').hide();
});

//keys

//consumer
//dPDkJA7iy5mKFDswMGvdDaEwT02CD94E38D9rtRF

//consumer secret
//0VbZUwIbH11kevTmDvvpa3eGzHTO3j2XVxfRBEQh

//javascript sdk
//f21465255bada4113e81e54fee25e68f2c287d4f

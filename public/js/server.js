let url= "wss://localhost:8001/liveStream";
let client= new WebSocket(url);


client.onopen= function (e){
    alert('Ahla');
}

client.onerror = function(error) {
    //alert(`[error] ${error}`);
    console.log(error)
};

function start(){

}
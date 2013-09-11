var WebSocketServer = require('ws').Server, wss = new WebSocketServer({port: 443});
var pg = require('pg'), listener, client, connString = 'postgres://digiplay_user@localhost/digiplay';
var in_use_keys = [];

wss.broadcast = function(channel, payload) {
	console.log(this.clients.length);
	for(var i in this.clients) {
		if(payload) {
			if(payload.location == this.clients[i].location) {
				try { this.clients[i].send(JSON.stringify({'channel': channel, 'payload': payload})); } 
				catch(err) { console.error(err); }				
			}
		} else {
			try { this.clients[i].send(JSON.stringify({'channel': channel})); } 
			catch(err) { console.error(err); }
		}
	}
};

function connectDB() {
	listener = new pg.Client(connString);
	listener.connect();
	listener.query('LISTEN t_log; LISTEN t_email; LISTEN t_playlists; LISTEN t_configuration; LISTEN t_audiowall; LISTEN t_showitems;');
	listener.on('notification', function(msg) { 
		console.log(msg);
		wss.broadcast(msg.channel, (msg.payload.length > 0 && JSON.parse(msg.payload))); 
	});

	client = new pg.Client(connString);
	client.connect();
}

wss.on('connection', function(ws) {
	ws.on('message', function(message) {
		console.log(message);
		data = JSON.parse(message);
		if(typeof data.ident != undefined) {
			client.query('SELECT location FROM configuration WHERE val = \''+data.ident+'\';', function(err, result) {
				if(typeof result != undefined) {
					ws.location = result.rows[0].location;
					in_use_keys.push(data.ident);
				} else {
					ws.close();
				}
			});
		}
	});
});

connectDB();

var h2 = require('spdy');

var options = {
  /*key: fs.readFileSync(__dirname + '/keys/spdy-key.pem'),
  cert: fs.readFileSync(__dirname + '/keys/spdy-cert.pem'),
  ca: fs.readFileSync(__dirname + '/keys/spdy-ca.pem'),*/

  spdy: {
    protocols: [ 'h2' , 'http/1.1' ],
    plain: true,

    connection: {
      windowSize: 1024 * 1024, // Server's window size
    }
  }
};

var server = h2.createServer(options, function(req, res) {
  console.log(req.method + ' ' + req.url);
  if (res.push) {
	  res.push('/main.js', {'content-type': 'application/javascript'}, function(err, stream) {
	    console.log("PUSH /main.js");
	    stream.end('alert("hello from push stream!")');
	  });
  }

  res.writeHead(200);
  res.end('Hello World! <script src="/main.js"></script>');
});

server.listen(8080);

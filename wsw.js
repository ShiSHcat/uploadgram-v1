module.exports = (res,req,command,WebSocket,unlinkAsync)=>{
    //console.log(req);
    res.header("Access-Control-Allow-Origin", "*");
    var { promisify } = require('util')
    var fs = require("fs");
    var unlinkAsync = promisify(fs.unlink)
    const ws = new WebSocket('ws://localhost:5433');
    var res2 = res;
    var e = 0;
    ws.on('message', ((res=>async(data) =>  {
        var de = JSON.parse(data);
        if(e == 0&&(de.ok)){
            var isue = (!!req.headers['user-agent'].match(/bot/));
            e++;
	        ws.send(JSON.stringify({
	            command:command,
                body:   req.body,
                paramz: req.params,
		        file:   req.file,
                get:    req.query,
                isbot: isue
            }) );
            return;
        } else {
	        if(de.ok){
	        	//console.log(data);
		        return;
	        }
	        if(de.opt == 2) {
	        	res.send(de.message);
		        return;
            }
            if(de.opt == 1) {
                console.log(de.header);
	        	res.set(de.header[0],de.header[1])
		        return;
            }
            if(de.opt == 3) {
                console.log(de.where);
                res.sendFile(de.where, function (err) {if(err)console.log(err);fs.unlink(de.where,(err)=>{if(err)console.log(err);})});
                
              return;
	        }
            if(de.command == "close") {
                try {
                    await unlinkAsync(req.file.path).catch((err)=>{});
                    await unlinkAsync(req.file.path+"dec").catch((err)=>{});
                    await unlinkAsync(req.file.path+"enc").catch((err)=>{});
                } catch (error) {
                    
                }

                return ws.close();
            }

        }

    })(res)));
}

module.exports = {
  apps : [{
    name   : "app1",
    script : "../TEKCOS/snceiwpeidwa.js",
    watch :true,
    error_file : "../../storage/logs/err.log",
    out_file : "../../storage/logs/out.log",
    ignore_watch : [".storage/logs/err.log",".storage/logs/out.log"],
  }]
}
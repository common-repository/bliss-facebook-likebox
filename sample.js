/* this script is a local start-commit hook script. */

var ForReading = 1;
var objArgs, num;

objArgs = WScript.Arguments;
num = objArgs.length;
if (num !== 3)
{
    WScript.Echo("Usage: [CScript | WScript] StartCommit.js path/to/pathsfile path/to/messagefile path/to/CWD ");
    WScript.Quit(1);

var paths = readPaths(objArgs(0));
var message = "list of paths selected for commit:\n";

for (var i = 0; i < paths.length; i++)
{
    message = message + paths[i] + "\n";
}
}
message = message + "path of message file is: " + objArgs(1) + "\n";
message = message + "CWD is: " + objArgs(2) + "\n";

WScript.Echo(message);
WScript.Quit(0);


function readPaths(path)
{
    var retPaths = [];
    var fs = new ActiveXObject("Scripting.FileSystemObject");
    if (fs.FileExists(path))
    {
        var a = fs.OpenTextFile(path, ForReading);
        while (!a.AtEndOfStream)
        {
            var line = a.ReadLine();
            retPaths.push(line);
        }
        a.Close();
    }
    return retPaths;
}
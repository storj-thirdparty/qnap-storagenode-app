method: GET
action: folders
parameter [optional]: path. (this would be the absolute path on the filesystem)
output: JSON 
sample request:
api.php?action=folders&path=<optional urlencoded path>
sample response: 
{ "cd" : "/usr/local/test-directory",
  "folders" : [ "dir1", "dir2", "dir3" ]}
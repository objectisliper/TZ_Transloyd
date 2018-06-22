<!doctype html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html">
  <title>Github API Webapp using jQuery - Treehouse Demo</title>
  <meta name="author" content="Jake Rocheleau">
  <link rel="shortcut icon" href="http://d15dxvojnvxp1x.cloudfront.net/assets/favicon.ico">
  <link rel="icon" href="http://d15dxvojnvxp1x.cloudfront.net/assets/favicon.ico">
  <link rel="stylesheet" type="text/css" media="all" href="css/styles.css">
  <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
</head>

<body>
  <div id="w">
    <h1>Simple Github API Webapp</h1>
    <p>Enter a single Github username below and click the button to display profile info via JSON.</p>
    
    <input type="text" name="ghusername" id="ghusername" placeholder="Github username...">
    
    <a href="#" id="ghsubmitbtn">Pull User Data</a>
    
    <div id="ghapidata" class="clearfix"></div>
    <div id="ghapirepo" class="clearfix"></div>
  </div>
<script type="text/javascript">
$(function(){
  $('#ghsubmitbtn').on('click', function(e){
    e.preventDefault();
    $('#ghapidata').html('<div id="loader"><img src="css/loader.gif" alt="loading..."></div>');
    
    var username = $('#ghusername').val();
    var requri   = 'https://api.github.com/users/'+username;
    var repouri  = 'https://api.github.com/users/'+username+'/repos';
    
    requestJSON(requri, function(json) {
      if(json.message == "Not Found" || username == '') {
        $('#ghapidata').html("<h2>No User Info Found</h2>");
      }
      
      else {
        
        var fullname   = json.name;
        var username   = json.login;
        var aviurl     = json.avatar_url;
        var profileurl = json.html_url;
        var location   = json.location;
        var followersnum = json.followers;
        var followingnum = json.following;
        var reposnum     = json.public_repos;
        var company    = json.company + ' - company';
        var email      = json.email;
        var registration_date = json.created_at;
        if(fullname == undefined) { fullname = username; };
        if(company == undefined) { company = ""};
        if(email == undefined) {email = ""};
        
        var outhtml = '<h2>'+fullname+' <span class="smallname">(@<a href="'+profileurl+'" target="_blank">'+username+'</a>)</span>, '+company+''+email+'</h2>';
        outhtml = outhtml + '<div class="ghcontent"><div class="avi"><a href="'+profileurl+'" target="_blank"><img src="'+aviurl+'" width="80" height="80" alt="'+username+'"></a></div>';
        outhtml = outhtml + '<p>Followers: '+followersnum+' - Following: '+followingnum+'<br>Repos: '+reposnum+'</p><br><p style="margin-top:-10px;"> account created at '+registration_date+'</p></div>';
        outhtml = outhtml + '<div class="repolist clearfix">';
        
        var repositories;
        $.getJSON(repouri, function(json){
          repositories = json;   
          outputPageContent();                
        });          
        
        function outputPageContent() {
          if(repositories.length == 0) { outhtml = outhtml + '<p>No repos!</p></div>'; }
          else {
            outhtml = outhtml + '<p><strong>Repos List:</strong></p> <ul>';
            $.each(repositories, function(index) {
              outhtml = outhtml + '<li><a href="#" id="repos-'+index+'">'+repositories[index].name + '</a></li>'; }); outhtml = outhtml + '</ul></div>';
            $('#ghapidata').html(outhtml);
            var outrepo; 
            $.each(repositories, function(index) {
              $('#repos-'+index).on('click', function(){
                e.preventDefault();
                $('#ghapirepo').html('<div id="loader"><img src="css/loader.gif" alt="loading..."></div>');
                  
                 
                  var repofullname = repositories[index].full_name;
                  var reponame     = repositories[index].name;
                  var repourl      = repositories[index].html_url; 
                  var repocreatedate = repositories[index].created_at;
                  var language     = repositories[index].language;
                  var issues       = repositories[index].open_issues;
                  var forks        = repositories[index].forks_count; 
                  var cloneurl     = repositories[index].clone_url;
                  var outrepo = '<h2>'+repofullname+' <span class="smallname">(@<a href="'+repourl+'" target="_blank">'+reponame+'</a>)</span></h2>';
                    outrepo = outrepo + '<div class="ghcontent"><div class="avi"><a href="'+profileurl+'" target="_blank"><img src="'+aviurl+'" width="80" height="80" alt="'+username+'"></a></div>';
                    outrepo = outrepo + '<p>'+language+' - language; '+issues+' - issues;<br>forks count:'+forks+'</p><br>';
                  var repocontenturl  = 'https://api.github.com/repos/'+username+'/'+reponame+'/contents/README.md'
                  
                    
                    outrepo = outrepo + '<div class="repolist clearfix">'
                    outrepo = outrepo + '<p><strong>actions:</strong></p> <ul>';
                    outrepo = outrepo + '<li><a href="'+cloneurl+'" id="copy">Copy</a></li>';
                    outrepo = outrepo + '<li><a href="#" id="fork" action="/page/users" method="post">Fork</a></li>';
                    outrepo = outrepo + '</ul></div>';
                    $('#ghapirepo').html(outrepo);
            });
            

          });
          
        } 
      } 
    }; 
  })}); 



  function requestJSON(url, callback) {
    $.ajax({
      url: url,
      complete: function(xhr) {
        callback.call(null, xhr.responseJSON);
      }
    });
  }
});
</script>
</body>
</html>

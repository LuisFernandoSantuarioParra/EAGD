<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>My drive</title>

    <script type="text/javascript">

        // The Browser API key obtained from the Google Developers Console.
        var developerKey = 'AIzaSyDHrterRLHR0yMJXK9RjujQBkofJMPZnZ0';

        // The Client ID obtained from the Google Developers Console.
        var clientId = '622193823323-3cpho0u6db5vmtb8gb51g8vhno9stfdl.apps.googleusercontent.com';

        // Scope to access user's drive.
        var scope = ['https://www.googleAPIs.com/auth/drive'];

        var pickerAPILoaded = false;
        var oauthToken;

        // Use the API Loader script to load google.picker and gAPI.auth.
        function onAPILoad() {
            gAPI.load('auth', {'callback': onAuthAPILoad});
        }

        function onAuthAPILoad() {
            window.gAPI.auth.authorize(
                {
                    'client_id': clientId,
                    'scope': scope,
                    'immediate': false
                },
                handleAuthResult);
        }
        function uploadFile()
        {
            if (oauthToken)
            {
                gAPI.load('picker', {'callback': onPickerAPILoad});
            }
            else
            {
                onAPILoad();
            }
        }
        function onPickerAPILoad() {
            pickerAPILoaded = true;
            createPicker();
        }

        function handleAuthResult(authResult) {
            if (authResult && !authResult.error) {
                oauthToken = authResult.access_token;
                var message = '<input type="button" onClick="logout()" value="Logout from Google">';
                document.getElementById('result').innerHTML = message;
            }
        }

        // Create and render a Picker object for picking user Photos.
        function createPicker() {
            var docsView = new google.picker.DocsView(google.picker.ViewId.FOLDERS).setIncludeFolders(true).setSelectFolderEnabled(true);
            if (pickerAPILoaded && oauthToken) {
                var picker = new google.picker.PickerBuilder().
                enableFeature(google.picker.Feature.MULTISELECT_ENABLED).
                addView(docsView).
                setOAuthToken(oauthToken).
                setDeveloperKey(developerKey).
                setCallback(pickerCallback).
                build();
                picker.setVisible(true);
            }
        }
        <img class="aligncenter size-full wp-image-5899" src="http://blog.designs.codes/wp-content/uploads/2014/11/Google-Drive-API-Key.jpg" alt="Google Drive API Key" width="580" height="387" />
            // A simple callback implementation.
            function pickerCallback(data) {
                var url = 'nothing';
                if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
                    var doc = data[google.picker.Response.DOCUMENTS][0];
                    url = doc[google.picker.Document.URL];
                }
                createPicker_upload(url);
            }
        function createPicker_upload(url) {
            var folder = url.split("folderview?id=");
            var folder_id = folder[1].split("&");
            Var uploadView = new google.picker.DocsUploadView().setParent(folder_id[0]);
            if (pickerAPILoaded && oauthToken) {
                var picker = new google.picker.PickerBuilder().
                enableFeature(google.picker.Feature.MULTISELECT_ENABLED).
                addView(uploadView).
                setOAuthToken(oauthToken).
                setDeveloperKey(developerKey).
                setCallback(pickerCallback_upload).
                build();
                picker.setVisible(true);
            }
        }
        function pickerCallback_upload(data) {
            var name = 'nothing';
            if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
                var doc = data[google.picker.Response.DOCUMENTS][0];
                name = doc[google.picker.Document.NAME];
                var message = 'You uploaded: ' + name + '<br><input type="button" onClick="logout()" value="Logout from Google">';
                document.getElementById('result').innerHTML = message;
            }
            /*else if (data[google.picker.Response.ACTION] == google.picker.Action.CANCEL)
            {
            document.location.href = "http://theletsstore.com/Indiewoods/user-centre/";
            }*/
        }
        function logout(){
            document.location.href = "https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=<WEBSITE-URL-YOU-WANT-TO-REDIRECT-TO>";
        }
        function createFolder() {
            if (oauthToken)
            {
                gAPI.client.load('drive', 'v2', function() {
                    var request = gAPI.client.request({
                        'path': '/drive/v2/files',
                        'method': 'POST',
                        'body':{
                            "title" : "New Folder",
                            "mimeType" : "application/vnd.google-apps.folder"
                        }
                    });
                    request.execute(function(resp) { console.log(resp); });
                });
            }
            else
            {
                onAPILoad();
            }
        }
    </script>
</head>
<body>
<h3>Google Drive Functions</h3>
<input type="button" value="Create New Folder" onClick="createFolder()">
<input type="button" value="Upload file" onClick="uploadFile()">
<div id="result"></div>
<!-- The Google API Loader script. -->
<script type="text/javascript" src="https://APIs.google.com/js/API.js?onload=onAPILoad"></script>
<script src="https://APIs.google.com/js/client.js"></script>
</body>
</html>
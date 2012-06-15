<h1>Wp Aweber</h1>
<p>Wp Aweber is a WordPress plugin that allows you to connect your acount to your WordPress website. Once connected, you are able to utilize the Aweber API to manipulate your account in whatever way you choose.</p>
<h2>Setup</h2>
<ol>
  <li>Install the plugin</li>
  <li>Create an <a href="https://labs.aweber.com/docs">Aweber Developer</a> account</li>
  <li>Create an Applicatio within your developer account</li>
  <li>Head to your WordPress Admin panel and find the Wp Aweber settings page under the Settings menu.</li>
  <li>Copy your consumer key and consumer secret fromy your new application to the plugin fields.</li>
  <li>Save the changes</li>
  <li>Press "Connect Account" and follow the instructions</li>
  <li>Once completed, the connection inidcator should be green and state "Account Connected"</li>
  <li>Your done!</li>
</ol>
<h2>Using the API</h2>
<p>Wp Aweber handles all of your oAuth work and gives you an object that represents your Aweber account. This object is generated directly from the Aweber API. To learn how to use this API, you can read their <a https://labs.aweber.com/docs">API documentation</a>.</p>
<h2>Notes</h2>
<ul>
<li>The current status of Wp Aweber makes it difficult to connect your account if your admin is using SSL. If this is the case, you may need to modify the redirection URLs in the plugin source to reflect an SSL connection.</li>
</ul>

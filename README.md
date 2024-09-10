# Buto-Plugin-ServerfilesServer

<ul>
<li>File server where to get, set, delete, list files.</li>
<li>Use plugin serverfiles/client to use this server from a client. Please read readme how to call this plugin.</li>
</ul>

<a name="key_0"></a>

## Settings

<p>Each folder must be registrated in theme settings file along with ip.</p>
<pre><code>plugin_modules:
  file:
    plugin: serverfiles/server
    settings:
      folders:
        -
          name: '/any_folder_relative_to_application_root'
          remote_addr:
            - 'Ip numbers to be able to connect.'</code></pre>

<a name="key_1"></a>

## Pages



<a name="key_1_0"></a>

### page_delete_file



<a name="key_1_1"></a>

### page_get_file



<a name="key_1_2"></a>

### page_get_folder



<a name="key_1_3"></a>

### page_set_file



<a name="key_2"></a>

## Methods



<a name="key_2_0"></a>

### log

<p>Log all request parameters.</p>
<pre><code>/../buto_data/theme/[theme]/plugin/serverfiles/server/log_241224.yml</code></pre>


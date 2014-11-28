set :application, "pizzza"
set :domain,      "#{application}.com.ua"
set :deploy_to,   "/srv/www/#{application}"
set :app_path,    "app"

set :repository,  "https://github.com/dsavin/pizzza.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :user, "root"

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        "185.25.119.100", :primary => true       # This may be the same as your `Web` server


set  :use_sudo,         false
set  :keep_releases,    3
set  :deploy_via,       :remote_cache
set  :web_path, "www"
set  :dump_assetic_assets, true
set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,     [app_path + "/logs", web_path + "/uploads", "vendor"]
set :use_composer, true

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL
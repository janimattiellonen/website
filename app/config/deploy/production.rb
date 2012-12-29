logger.level = Logger::MAX_LEVEL

default_run_options[:pty] = true
ssh_options[:forward_agent] = true

set :branch, fetch(:branch, "master")

set :domain,      "janimattiellonen.com"
set :deploy_to,   "/var/www/vhosts/janimattiellonen.com/website/cap"

set :user, 'capistrano'
set :use_sudo, false

ssh_options[:keys] = [File.join(ENV["HOME"], ".ssh", "id_rsa-capistrano")]

role :web, domain                        # Your HTTP server, Apache/etc
role :app, domain                        # This may be the same as your `Web` server
role :db, domain, :primary => true       # This is where Rails migrations will run

set :keep_releases,  3

set :shared_files, ["app/config/parameters.yml"]
set :shared_children, [app_path + "/logs", "node_modules", web_path + "/files"]

set :dump_assetic_assets, true
set :use_composer, true

after "deploy:finalize_update" do
  run "chown -R capistrano:www-data #{latest_release}/#{cache_path}"
  run "chown -R capistrano:www-data #{latest_release}/#{log_path}"
  run "chmod -R 775 #{latest_release}/#{cache_path}"
end
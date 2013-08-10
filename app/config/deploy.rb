set :stage_dir, 'app/config/deploy' # needed for Symfony2 only
require 'capistrano/ext/multistage'
set :stages, %w(production)

set :repository,  "git@github.com:janimattiellonen/website.git"
set :scm,         :git

#set  :keep_releases,  3

set :application, "Janimattiellonen-website"

set :model_manager, "doctrine"

set :app_path,    "app"
set :web_path,    "web"

set :deploy_via, :remote_cache

set :use_sudo, false

set :writable_dirs, ["app/cache", "app/logs"]
set :webserver_user, "www-data"
#set :permission_method, :acl
before "deploy:restart", "deploy:set_permissions"

namespace :symfony do
  namespace :assets do
    desc "Updates assets version"
    task :update_version do
        run "sed -i 's/\\(%assets_version%\\)/ #{release_name}/g' #{latest_release}/app/config/config.yml"
    end
  end
end

namespace :jme do
   namespace :js_routes do
        desc "Dumps js routes"
        task :dump_js_routes do
            run "cd #{release_path} && php app/console fos:js-routing:dump --locale fi"
        end
   end

   namespace :lus do
        desc "Test"
        task :barf do
            run "which less"
        end
   end
end

before "symfony:assetic:dump" do
  symfony.assets.update_version
end

#after "deploy:update", "deploy:cleanup"

after "deploy:update" do
    #jme.js_routes.dump_js_routes
end
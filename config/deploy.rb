# config valid only for current version of Capistrano
lock "3.7.2"

set :ssh_options, {:forward_agent => true}

set :application, "guestbook"
set :repo_url, "git@github.com:ltalavera/guestbook.git"
set :deploy_via, :remote_cache

# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
set :deploy_to, "/var/www/guestbook"

# The version of laravel being deployed
set :laravel_version, 5.4

# Ensure the dirs in :linked_dirs exist?
set :laravel_ensure_linked_dirs_exist, false

# Link the directores in laravel_linked_dirs?
set :laravel_set_linked_dirs, false

# Ensure the paths in :file_permissions_paths exist?
set :laravel_ensure_acl_paths_exist, false

# Set ACLs for the paths in laravel_acl_paths?
set :laravel_set_acl_paths, false

# Default value for :format is :airbrussh.
# set :format, :airbrussh

# You can configure the Airbrussh format using :format_options.
# These are the defaults.
# set :format_options, command_output: true, log_file: "log/capistrano.log", color: :auto, truncate: :auto

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
# append :linked_files, "config/database.yml", "config/secrets.yml"

# Default value for linked_dirs is []
# append :linked_dirs, "log", "tmp/pids", "tmp/cache", "tmp/sockets", "public/system"

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }
SSHKit.config.command_map[:composer] = "/usr/local/bin/composer"

# Default value for keep_releases is 5
# set :keep_releases, 5

# # you can use overlay for setting sensetive information.
set :app_path, '/var/www/guestbook/current'
set :app_debug, false
set :app_key, 'base64:NzETbTW3nZ+egFC4891HAlBIQnzlF63+AUczGqWjOSg='

namespace :environment do
    desc "Set environment variables"
    task :set_variables do
        on roles(:app) do
              puts ("--> Create environment configuration file")
              execute "cat /dev/null > #{fetch(:app_path)}/.env"
              execute "echo APP_DEBUG=#{fetch(:app_debug)} >> #{fetch(:app_path)}/.env"
              execute "echo APP_KEY=#{fetch(:app_key)} >> #{fetch(:app_path)}/.env"
        end
    end
end

namespace :composer do
    desc "Running Composer Install"
    task :install do
        on roles(:app) do
            within release_path do
                execute :composer, "install --no-dev --optimize-autoloader" # install dependencies
                execute :composer, "dumpautoload"
                #execute :chmod, "u+x artisan" # make artisan executable
            	#execute :php, "artisan migrate" # run migrations
            end
        end
    end
end

namespace :permissions do
	desc "Set permissions to folders"
    task :restart do
        on roles(:app) do
            within release_path  do
                execute :chmod, "-R 777 storage"
                execute :chmod, "-R 777 bootstrap/cache"
            end
        end
    end
end

namespace :deploy do
  #after :updated, "composer:install"
  #after :finished, "environment:set_variables"
  after :finished, "permissions:restart"
end

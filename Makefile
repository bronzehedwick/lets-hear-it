default: web ## web.

help: ## Prints help for targets with comments.
	@grep -E '^[a-zA-Z._-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

sync: ## Push the site to the server.
	@rsync --recursive --delete --rsh=ssh --exclude=".*" --quiet web/ waitstaff_deploy:/usr/local/www/letshearit.network

web: sync ## Deploys site to server.

serve: ## Start development server in the background.
	@cd web && python -m http.server

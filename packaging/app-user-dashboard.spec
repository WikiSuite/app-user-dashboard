
Name: app-user-dashboard
Epoch: 1
Version: 2.5.5
Release: 1%{dist}
Summary: User Dashboard
License: GPLv3
Group: Applications/Apps
Packager: WikiSuite
Vendor: WikiSuite
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = 1:%{version}-%{release}
Requires: app-base
Requires: app-accounts
Requires: app-groups
Requires: app-users

%description
The User Dashboard app provides information and settings for end users (including info and links from installed apps like Syncthing, etc.)

%package core
Summary: User Dashboard - API
License: LGPLv3
Group: Applications/API
Requires: app-base-core
Requires: app-base >= 1:2.5.42
Requires: app-accounts-core
Requires: app-groups-core
Requires: app-users-core

%description core
The User Dashboard app provides information and settings for end users (including info and links from installed apps like Syncthing, etc.)

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/user_dashboard
cp -r * %{buildroot}/usr/clearos/apps/user_dashboard/

install -D -m 0644 packaging/user_dashboard.acl %{buildroot}/var/clearos/base/access_control/authenticated/user_dashboard

%post
logger -p local6.notice -t installer 'app-user-dashboard - installing'

%post core
logger -p local6.notice -t installer 'app-user-dashboard-api - installing'

if [ $1 -eq 1 ]; then
    [ -x /usr/clearos/apps/user_dashboard/deploy/install ] && /usr/clearos/apps/user_dashboard/deploy/install
fi

[ -x /usr/clearos/apps/user_dashboard/deploy/upgrade ] && /usr/clearos/apps/user_dashboard/deploy/upgrade

exit 0

%preun
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-user-dashboard - uninstalling'
fi

%preun core
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-user-dashboard-api - uninstalling'
    [ -x /usr/clearos/apps/user_dashboard/deploy/uninstall ] && /usr/clearos/apps/user_dashboard/deploy/uninstall
fi

exit 0

%files
%defattr(-,root,root)
/usr/clearos/apps/user_dashboard/controllers
/usr/clearos/apps/user_dashboard/htdocs
/usr/clearos/apps/user_dashboard/views

%files core
%defattr(-,root,root)
%exclude /usr/clearos/apps/user_dashboard/packaging
%exclude /usr/clearos/apps/user_dashboard/unify.json
%dir /usr/clearos/apps/user_dashboard
/usr/clearos/apps/user_dashboard/deploy
/usr/clearos/apps/user_dashboard/language
/usr/clearos/apps/user_dashboard/libraries
/var/clearos/base/access_control/authenticated/user_dashboard

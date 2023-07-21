<?php
$config['username_domain'] = getenv('ROUNDCUBEMAIL_USERNAME_DOMAIN');
$config['imap_conn_options'] = array(
    'ssl' => [
         'verify_peer'       => true,
         'allow_self_signed' => true,
//         'peer_name'         =>  'imap.your.domain.com',
         'verify_peer_name' => false,
    ],
    );

    $config['smtp_conn_options'] = array(
      'ssl'=> array(
          'verify_peer'      => true,
//          'peer_name'        =>  'smtp.your.domain.com',
          'allow_self_signed'=> true,
          'verify_peer_name' => false,
      ),
    );

// Samba AD DC Address Book
$config['autocomplete_addressbooks'] = array('global_ldap_book');
if ((getenv('ROUNDCUBE_AUTOCOMPLETE_ADDRESS_BOOK_HOST')!== false) &&
    (getenv('ROUNDCUBE_AUTOCOMPLETE_ADDRESS_BOOK_BASE_DN')!== false) &&
    (getenv('ROUNDCUBE_AUTOCOMPLETE_ADDRESS_BOOK_BIND_DN')!== false) &&
    (getenv('ROUNDCUBE_AUTOCOMPLETE_ADDRESS_BOOK_BIND_PASS')!== false)){
    $config['ldap_public']["global_ldap_book"] = array(
        'name'              => 'Mailboxes',
        'hosts'             => array(getenv('ROUNDCUBE_AUTOCOMPLETE_ADDRESS_BOOK_HOST')),
        'port'              => 389,
        'use_tls'           => false,
        'ldap_version'      => '3',
        'network_timeout'   => 10,
        'user_specific'     => false,
        'base_dn'       => getenv('ROUNDCUBE_AUTOCOMPLETE_ADDRESS_BOOK_BASE_DN'),
        'bind_dn'       => getenv('ROUNDCUBE_AUTOCOMPLETE_ADDRESS_BOOK_BIND_DN'),
        'bind_pass'     => getenv('ROUNDCUBE_AUTOCOMPLETE_ADDRESS_BOOK_BIND_PASS'),
        'writable'      => false,
        'search_fields' => array(
            'cn',
            'uid',
        ),
        'fieldmap' => array(
            'name'          => 'gecos',
            'surname'       => 'sn',
            'firstname'     => 'givenName',
            'title'         => 'title',
            'email'         => 'mail:*',
            'phone:work'    => 'telephoneNumber',
            'phone:mobile'  => 'mobile',
            'phone:workfax' => 'facsimileTelephoneNumber',
            'zipcode'       => 'postalCode',
            'department'    => 'department',
            'notes'         => 'description',
            'photo'         => 'jpegPhoto',
        ),
        'sort'          => 'uid',
        'scope'         => 'sub',
        'filter'        => '(&(|(objectclass=person))(!(mail=archive@'.getenv('DOMAIN').')))',
        'fuzzy_search'  => true,
        'vlv'           => false,
        'sizelimit'     => '0',
        'timelimit'     => '0',
        'referrals'     => false,
        'groups'  => [
            'base_dn'           => getenv('ROUNDCUBE_AUTOCOMPLETE_ADDRESS_BOOK_BASE_DN'),
            'scope'             => 'sub',       // Search mode: sub|base|list
            'filter'            => '(objectClass=posixGroup)',
            'object_classes'    => ['top', 'groupOfNames'],   // Object classes to be assigned to new groups
            'member_attr'       => 'memberUid',   // Name of the default member attribute, e.g. uniqueMember
            'name_attr'         => 'cn',       // Attribute to be used as group name
            'email_attr'        => 'mail',     // Group email address attribute (e.g. for mailing lists)
            'member_filter'     => '(objectclass=*)',  // Optional filter to use when querying for group members
            'vlv'               => false,      // Use VLV controls to list groups
            'class_member_attr' => [      // Mapping of group object class to member attribute used in these objects
                'groupofnames'       => 'member',
                'groupofuniquenames' => 'uniquemember'
            ],
        ],
        'group_filters' => array(
            'departments' => array(
            'name'    => 'Lists',
            'scope'   => 'sub',
            'base_dn' => getenv('ROUNDCUBE_AUTOCOMPLETE_ADDRESS_BOOK_BASE_DN'),
            'filter'  => '(objectClass=posixGroup)',
            ),
        ),
    );
}

$config['plugins'] = [
    'archive',
    'zipdownload',
];
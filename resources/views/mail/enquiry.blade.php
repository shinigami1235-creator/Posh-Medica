New message from poshmedica.co.th

Topic: {{ __('posh.topics.' . $e->topic, [], 'th') }} ({{ __('posh.topics.' . $e->topic, [], 'en') }})
Name: {{ $e->name }}
Email: {{ $e->email }}
Phone: {{ $e->phone ?: '-' }}
Language: {{ strtoupper($e->locale) }}
Attachment: {{ $e->attachment ? 'yes, open it in the admin' : 'none' }}

{{ $e->message }}

Open in the admin: {{ url('admin/enquiries/' . $e->id) }}

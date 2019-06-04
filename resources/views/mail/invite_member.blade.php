<p>An invitation has been sent to you.</p>
<p>click on the link to confirm the invitation</p>

<a href="{{ route('invite', ['token' => $member->invite_token]) }}">Confirm Invite</a>
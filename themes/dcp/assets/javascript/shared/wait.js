export async function until (condition, intervalMs = 50, timeoutMs = 30_000 ) {
    return new Promise((resolve) => {
        waitUntil( condition, resolve, intervalMs, timeoutMs );
    });
}

export function waitUntil ( condition, callback, intervalMs = 50, timeoutMs = 30_000 ) {
	const initialValue = condition();
	if ( initialValue ) {
		return callback( initialValue );
	}

	let elapsed = 0;
	let interval = window.setInterval( () => {
		const value = condition();
		if ( value ) {
			window.clearInterval( interval );
			return callback( value );
		}

		elapsed += intervalMs;
		if ( elapsed >= timeoutMs ) {
			window.clearInterval( interval );
		}
	}, intervalMs );
}

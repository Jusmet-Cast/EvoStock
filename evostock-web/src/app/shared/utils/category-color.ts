const PALETTE_SIZE = 6;

/**
 * Deterministic pastel color class for a category chip, derived from its
 * name so the same category always renders the same color without any
 * manual per-category configuration.
 */
export function categoryChipClass(name: string): string {
  let hash = 0;
  for (let i = 0; i < name.length; i++) {
    hash = (hash * 31 + name.charCodeAt(i)) | 0;
  }
  const index = Math.abs(hash) % PALETTE_SIZE;
  return `cat-color-${index}`;
}

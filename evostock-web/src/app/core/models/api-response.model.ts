export interface ApiResponse<T> {
  data: T;
}

export interface ApiValidationError {
  message: string;
  errors: Record<string, string[]>;
}
